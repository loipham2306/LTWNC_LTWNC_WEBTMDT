import React from 'react';

const PageHeader = ({ title, breadcrumb }) => {
  return (
    <div className="container-fluid page-header py-5" style={{
      background: 'linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url("/img/carousel-1.jpg")',
      backgroundSize: 'cover',
      backgroundPosition: 'center'
    }}>
        <h1 className="text-center text-white display-6 wow fadeInUp" data-wow-delay="0.1s">
            {title}
        </h1>
        
        <ol className="breadcrumb justify-content-center mb-0 wow fadeInUp" data-wow-delay="0.3s">
            <li className="breadcrumb-item active text-white">{breadcrumb}</li>
        </ol>
    </div>
  );
};

export default PageHeader;